<?php namespace Admin;

use Synonymous;
use View, Input, Redirect, URL, Session, Response;

class SynonymsController extends AdminController
{
    protected $synRepo;

    function __construct( \SynonymousRepository $synRepo )
    {
        $this->synRepo = $synRepo;
    }

    public function index()
    {
        $synonyms = $this->synRepo->getAll();

        $this->layout->content = View::make('admin.synonyms.index')
            ->with('synonyms', $synonyms);
    }

    public function create()
    {
        $synonymous = $this->synRepo->newSym();

        $this->layout->content = View::make('admin.synonyms.create')
            ->with('synonymous', $synonymous);
    }

    public function store()
    {
        $synonymous = new Synonymous();
        $synonymous->fill( Input::all() );

        if ($synonymous->save()) {
            return Redirect::action('Admin\SynonymsController@index');
        } else {
                        // Get validation errors
            $error = $synonymous->errors->all();

            return Redirect::action('Admin\SynonymsController@create')
                    ->withInput()
                    ->with( 'error', $error );
        }
    }

    public function edit($id)
    {
        $synonymous = $this->synRepo->findBy($id);

        $this->layout->content = View::make('admin.synonyms.edit')
            ->with('synonymous', $synonymous)
            ->with('action', 'Admin\SynonymsController@update')
            ->with('method', 'PUT');
    }

    public function update($id)
    {
        $synonymous = $this->synRepo->findBy($id);
        $synonymous->fill( Input::all() );

        if ($synonymous->isValid() && $synonymous->save()) {
            return Redirect::action('Admin\SynonymsController@index');
        } else {
            // Get validation errors
            $error = $synonymous->errors->all();

            return Redirect::action('Admin\SynonymsController@edit', ['id' => $synonymous->_id])
                    ->withInput()
                    ->with( 'error', $error );
        }
    }

    public function destroy($id)
    {
        $synonymous = $this->synRepo->findBy($id);

        $synonymous->delete();

        return Redirect::action('Admin\SynonymsController@index');
    }
}
