<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
    print json_encode($this->res); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>