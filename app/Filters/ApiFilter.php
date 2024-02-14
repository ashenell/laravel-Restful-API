<?php

namespace App\Filters;

use Illuminate\Http\Request;


class ApiFilter {
    /**
     * @var array
     * Allowed query parameters for the customer query class.
     * we can use these parameters to filter the query.
     * 
     */
    protected $safeParams = [];

    protected $columnMap = [];

    protected $operatorMap = [];

    public function transform(Request $request) {
        /**
         * @var array
         * The query parameters that will be used to filter the query.
         * 
         */
        $eloQuery = [];
        
        foreach ($this->safeParams as $param => $operators) { // Loop through the safeParams array
            $query = $request->query($param); // Get the query parameter from the request

            if (!isset($query)) { // If the query parameter is not set, continue to the next iteration
                continue;
            }

            $column = $this->columnMap[$param] ?? $param; // Get the column name from 
            //the columnMap array or use the query parameter as the column name

            foreach ($operators as $operator) { // Loop through the operators array
                if (isset($query[$operator])) { // If the operator is set in the query parameter
                    // Add the column, operator, and value to the eloQuery array
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }


        return $eloQuery;
    }

}