<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia                                                                       */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace ChronopostPickupPoint\Loop;

use ChronopostPickupPoint\Controller\ChronopostPickupPointRelayController;
use ErrorException;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\Address;
use Thelia\Model\AddressQuery;
use Thelia\Model\CountryQuery;

/**
 * Class ChronopostPickupPointGetRelay
 * @package ChronopostPickupPoint\Loop
 *
 * @method string getOrderWeight
 * @method string getZipcode
 * @method string getCity
 * @method string getCountryid
 * @method string getAddress
 */
class ChronopostPickupPointGetRelay extends BaseLoop implements ArraySearchLoopInterface
{
    /**
     * @inheritdoc
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createAnyTypeArgument('orderweight', '', true),
            Argument::createAnyTypeArgument('countryid', ''),
            Argument::createAnyTypeArgument('zipcode', ''),
            Argument::createAnyTypeArgument('city', ''),
            Argument::createAnyTypeArgument('address', '')
        );
    }

    /**
     * @return array|mixed
     * @throws ErrorException
     * @throws PropelException
     */
    public function buildArray()
    {
        // Find the address ... To find ! \m/
        $orderWeight = $this->getOrderweight();
        $zipcode = $this->getZipcode();
        $city = $this->getCity();
        $countryId = $this->getCountryid();
        $address1 = $this->getAddress();

        $addressId = null;
        //$addressId = $this->getAddress();

        if (!empty($addressId) && (!empty($zipcode) || !empty($city))) {
            throw new \InvalidArgumentException(
                "Cannot have argument 'address' and 'zipcode' or 'city' at the same time."
            );
        }

        if (null !== $addressModel = AddressQuery::create()->findPk($addressId)) {
            $address = array(
                'orderweight' => $orderWeight,
                'zipcode' => $addressModel->getZipcode(),
                'city' => $addressModel->getCity(),
                'address' => $addressModel->getAddress1(),
                'countrycode' => $addressModel->getCountry()->getIsoalpha2()
            );
        } elseif (empty($zipcode) || empty($city)) {
            $search = AddressQuery::create();

            $customer = $this->securityContext->getCustomerUser();
            if ($customer !== null) {
                $search->filterByCustomerId($customer->getId());
                $search->filterByIsDefault('1');
            } else {
                throw new ErrorException('Customer not connected.');
            }

            $search = $search->findOne();
            $address['orderweight'] = $orderWeight;
            $address['zipcode'] = $search->getZipcode();
            $address['city'] = $search->getCity();
            $address['address'] = $search->getAddress1();
            $address['countrycode'] = $search->getCountry()->getIsoalpha2();
        } else {
            $address = array(
                'orderweight' => $orderWeight,
                'zipcode' => $zipcode,
                'city' => $city,
                'address' => $address1,
                'countrycode' => CountryQuery::create()
                    ->findOneById($countryId)
                    ->getIsoalpha2()
            );
        }

        // Then ask the Web Service
        $request = new ChronopostPickupPointRelayController();

        try {
            $response = $request->findByAddress($address['orderweight'], $address['address'], $address['zipcode'], $address['city'], $address['countrycode']);
        } catch (InvalidArgumentException $e) {
            $response = array();
        } catch (\Exception $e) {
            $response = array();
        }

        if (!is_array($response) && $response !== null) {
            $newResponse[] = $response;
            $response = $newResponse;
        }

        return $response;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $item) {
            $loopResultRow = new LoopResultRow();

            foreach ($item as $key => $value) {
                $loopResultRow->set(strtoupper($key), $value);
            }

            // format distance
            $distance = (string) $loopResultRow->get('DISTANCEENMETRE');
            if (strlen($distance) < 4) {
                $distance .= ' m';
            } else {
                $distance = (string)(float)$distance / 1000;
                while (substr($distance, strlen($distance) - 1, 1) == "0") {
                    $distance = substr($distance, 0, strlen($distance) - 1);
                }
                $distance = str_replace('.', ',', $distance) . ' km';
            }
            $loopResultRow->set('distance', $distance);

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
