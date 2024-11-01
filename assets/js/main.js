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

jQuery(
	function ($) {
		$( document ).ready(
			function () {
				$( '#smconnector_use_plugin_tmp_dir' ).change(
					function () {
						console.log( this.checked );
						$( '#smconnector_tmp_dir' ).prop( 'disabled', this.checked );
					}
				);
			}
		);
	}
);
