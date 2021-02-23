<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public  function __construct($resource, $user = null)
    {
        if ($user) {
            if (\is_array($resource) || $resource instanceof Collection) {
                foreach ($resource as $res) {
                    $res->rights = $this->getRightForModel($user, $res);
                }
            } else {
                $resource->rights = $this->getRightForModel($user, $resource);
            }
        }
        parent::__construct($resource);
    }

    public function getRightForModel($user, $model) {
        return [
            'view' => $user->can('view', $model),
            'update' => $user->can('update', $model),
            'delete' => $user->can('delete', $model),
        ];
    }
}
