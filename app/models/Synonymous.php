<?php

class Synonymous extends BaseModel
{

    /**
     * The database collection
     *
     * @var string
     */
    protected $collection = 'synonyms';

    /**
     * Factory definition
     *
     * @var array
     */
    public static $factory = array(
        'related_word' => array()
    );

    public $guarded = array(
        '_PUT',
        '_method'
    );

    public static $rules = array(
        'word' => 'required',
        'related_word' => 'required'
    );

    public function save($force=false)
    {
        if( $this->isValid() )
        {
            if(parent::save( $force ))
            {
                $this->insertSynonyms();
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function setAttribute($key, $value)
    {
        if($key == 'related_word')
        {
            if(is_string($value))
            {
                $value = array_map('trim',explode(",",strtolower($value)));
            }
        }

        return parent::setAttribute($key, $value);
    }

    public function insertSynonyms()
    {
        $requestURL = Config::get('search_engine.settings.elastic_search.connection_url');
        $appName = Config::get('search_engine.application_name');

        // Closing indexes
        $requestURL = str_replace(':9200', '', $requestURL);
        $request = $requestURL . "/" . $appName . "/_close";
        $this->execute("POST", $request, "9200");

        $synonyms = array();

        // Preparing to update index
        foreach (Synonymous::all() as $sym) {
            $related_word = implode(', ', $sym->related_word);
            array_push($synonyms, "$related_word => $sym->word");
        }

        $parameters = [
            'index' => [
                'analysis' => [
                    'filter' => [
                        'synonym' => [
                            'type' => 'synonym',
                            'synonyms' => $synonyms
                        ]
                    ]
                ]
            ]
        ];

        // updating indexes
        $request = $requestURL . "/" . $appName .  "/_settings";

        $this->execute("PUT", $request, "9200", $parameters);


        // closing
        $request = $requestURL . "/" . $appName . "/_open";

        $this->execute("POST", $request, "9200");

    }

    private function execute($method, $url, $port, $parameters=array())
    {
        $conn = curl_init();

        curl_setopt($conn, CURLOPT_URL, $url);
        curl_setopt($conn, CURLOPT_TIMEOUT, 5);
        curl_setopt($conn, CURLOPT_PORT, $port);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($conn, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($conn, CURLOPT_FORBID_REUSE , 0) ;

        if (is_array($parameters) && count($parameters) > 0)
            curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($parameters));
        else
            curl_setopt($conn, CURLOPT_POSTFIELDS, null);

        curl_exec($conn);
    }
}
