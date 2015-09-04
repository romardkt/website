<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    protected $fillable = [
        'name',
        'street',
        'city',
        'state',
        'zip',
        'comments',
    ];
    protected $_addNames = [
        'Otto Armleder Memorial Park',
        'Winton Woods Frisbee Golf Course',
        'Heritage Oak Park',
    ];

    public function address()
    {
        return '<strong>'.$this->name.'</strong><br/>'
            .$this->street.'<br/>'
            .$this->city.', '.$this->state.' '.$this->zip.'<br/>';
    }

    public static function fetchOrCreateLocation($data)
    {
        if (isset($data['street']) && isset($data['city']) && isset($data['state']) && isset($data['zip'])) {
            $location = self::fetchLocation($data['street'], $data['city'], $data['state'], $data['zip']);

            if (!$location) {
                $location = static::create([
                    'name' => (empty($data['name'])) ? $data['street'] : $data['name'],
                    'street' => $data['street'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zip' => $data['zip'],
                    'comments' => (empty($data['comments'])) ? null : $data['comments'],
                ]);
            }

            return $location;
        }

        return;
    }

    public static function fetchLocation($street, $city, $state, $zip)
    {
        return static::where('street', '=', $street)
            ->where('city', '=', $city)
            ->where('state', '=', $state)
            ->where('zip', '=', $zip)
            ->first();
    }

    public static function fetchForSelect()
    {
        $locations = [0 => 'Select Location'];
        foreach (static::orderBy('name')->orderBy('street')->get() as $location) {
            $locations[$location->id] = $location->name;
        }

        return $locations;
    }

    private function getMapString()
    {
        $name = trim(preg_replace('/\([^)]+\)/', '', $this->name));
        if ($this->name == $this->street || !in_array($name, $this->_addNames)) {
            $name = null;
        }

        $data = [
            $name,
            $this->street,
            $this->city,
            $this->state,
            $this->zip,
        ];

        return urlencode(trim(implode(' ', $data)));
    }

    public function getUrl()
    {
        return 'https://www.google.com/maps/place/'.$this->getMapString();
    }

    public function getImage($zoom = 16)
    {
        return 'https://maps.googleapis.com/maps/api/staticmap?center='.$this->getMapString()
            .'&zoom='.$zoom
            .'&size=650x400&markers='.$this->getMapString()
            .'&sensor=false';
    }
}
