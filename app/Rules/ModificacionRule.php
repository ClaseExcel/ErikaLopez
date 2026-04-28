<?php

namespace App\Rules;

use App\Models\Modificacion;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class ModificacionRule implements Rule
{
    protected $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $data = Modificacion::where('compania_id', $this->request->input('compania_id'))
                            ->where('movimiento', $this->request->input('movimiento'))
                            ->where('periodo', $this->request->input('periodo'))
                            ->first();
        // Si $data no es null (es decir, la consulta encontró un registro), devuelve false (falla la validación).
        return $data === null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ya existe un registro con los mismos valores.';
    }
}
