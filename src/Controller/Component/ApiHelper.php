<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\Error\Debugger;
use Cake\Utility\Hash;
use Cake\Utility\Set;
use App\Model\Entity\Flyer;
use App\Model\Table\FlyersTable;
use Cake\ORM\TableRegistry;


class ApiHelper {
    private array $arr;

    public function __construct() {
    }

    public function setArray($arr) {
        $this->arr = $arr;
    }

    public function getArray(): array {
        return $this->arr;
    }

    function isInteger($value){
        return(ctype_digit(strval($value)));
    }

    public function checkValue($value, $default): int {
        if ($value === null || $this->isInteger($value) === false)
            return $default;
        return intval($value, 10);
    }

    public function checkFields($fields, $keys): string {
        $error_str = '';
        foreach ($fields as $field) {
            if (in_array($field, $keys) === false) {
                $error_str .= $field . ', ';
            }
        }
        return rtrim($error_str, ', ');
    }

    public function applyFilters($filters_query) {
        foreach ($filters_query as $key => $value) {
            $this->arr = Hash::extract($this->arr, '{*}[' . $key . '=' . $value . ']');
        }
    }

    public function applyFields($fields, $keys) {
        foreach($keys as $key) {
            if (in_array($key, $fields) !== true) 
                $this->arr = Hash::remove($this->arr, '{*}.' . $key);
        }
    }

    public function paginate($page, $limit) {
        $from = ($page - 1) * $limit;
        $this->arr = array_slice($this->arr, $from, $limit);
    }
}

