<?php
namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiMessage extends JsonResponse {
    public static $descriptions = array(
        'entity_not_found' => array(404, 'The requested entity could not be foudn'),
        'entity_referenced' => array(400, 'The entity is referenced by other entities')
    );

    protected $type;
    protected $description;

    public static function fromType($type) {
        $msg = new self($type);
        if(self::$descriptions[$type] === null) return $msg;
        $msg->setStatusCode(self::$descriptions[$type][0]);
        $msg->setDescription(self::$descriptions[$type][1]);
        return $msg;
    }

    function __construct($type = '', $description = null, $statusCode = 200) {
        $this->type = $type;
        $this->description = $description;
        parent::__construct(null, $statusCode);
        $this->_update();
    }

    private function _update() {
        $this->setData(array(
            'code' => $this->getStatusCode(),
            'type' => $this->type,
            'description' => $this->description
        ));
    }

    public function setType($type = '') {
        $this->type = $type;
        $this->_update();
    }

    public function getType() {
        return $this->type;
    }

    public function setDescription($description = '') {
        $this->description = $description;
        $this->_update();
    }

    public function getDescription() {
        return $this->description;
    }
}
