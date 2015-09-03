<?php

namespace Cupa\Http\Controllers;

use Cupa\CupaForm;

class FormController extends Controller
{
    public function view($slug)
    {
        $form = CupaForm::fetchBySlug($slug);
        if ($form) {
            $file = public_path().$form->location;
            if (file_exists($file)) {
                return response()->download($file);
            }
        }

        Session::flash('msg-error', 'Could not find form to download.');

        return redirect()->to(Session::get('previous'));
    }
}
