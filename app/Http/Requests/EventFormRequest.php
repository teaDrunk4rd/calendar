<?php

namespace App\Http\Requests;

use App\Event;
use Illuminate\Foundation\Http\FormRequest;

class EventFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'date' => 'required',
            'type_id' => 'required',
            'id' => '',
            'description' => '',
            'creator_id' => '',
            'closed_at' => ''
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Заполните наименование',
            'name.max' => 'Слишком длинное наименование',
            'date.required' => 'Заполните дату начала',
            'type_id.required' => 'Заполните тип'
        ];
    }

    public function authorize() {
        if ($this->id == null) return true;

        $event = Event::find($this->id);

        return $event && $this->creator_id == $event->creator_id;
    }
}
