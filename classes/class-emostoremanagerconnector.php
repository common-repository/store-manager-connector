<?php
/**
 *  This file is part of Store Manager Connector.
 *
 *   Store Manager Connector is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Store Manager Connector is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Mobile Assistant Connector. If not, see <http://www.gnu.org/licenses/>.
 *
 *  author    eMagicOne <contact@emagicone.com>
 *  copyright 2024 eMagicOne
 *  license   http://www.gnu.org/licenses   GNU General Public License
 *
 *  @package eMagicOne Store Manager for WooCommerce
 */

/**
 * Class which prepare plugin structure
 */
class EmoStoreManagerConnector {
	const MODULE_VERSION        = '1.2.3';
	const SMCONNECTOR_VERSION   = 11;

	/** The shop cart overrider
	 *
	 * @var object $shop_cart_overrider The shop cart object.
	 */
	private $shop_cart_overrider;

	/** Prepare plugin structure */
	public function __construct() {
		$this->shop_cart_overrider = new EmoSMCWoocommerceOverrider( EMO_SMC_MODULE_NAME, EMO_SMC_OPTIONS_NAME );
		new EmoSMConnectorCommon( $this->shop_cart_overrider, self::MODULE_VERSION, self::SMCONNECTOR_VERSION );
	}
}
