<?php
namespace App\Response;

class ApiError extends ApiMessage {
    public static $descriptions = array(
        'not_found' => array(404, 'The requested entity could not be foudn'),
        'entity_referenced' => array(400, 'The entity is referenced by other entities')
    );
    function __construct($type = '') {
    }
}
