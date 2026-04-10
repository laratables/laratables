<?php

use Freshbitsweb\Laratables\Exceptions\InvalidMaxLimit;

if (! function_exists('isRelationColumn')) {
    /**
     * Decides whether specified column is a relation table column.
     *
     * @param string Name of the column
     * @return bool
     */
    function isRelationColumn($columnName)
    {
        return strpos($columnName, '.') !== false;
    }
}

if (! function_exists('getRelationDetails')) {
    /**
     * Returns the relation details from the specified column.
     * Supports nested relations like "company.address.city".
     *
     * @param string $columnName Name of the column (e.g., "company.address.city")
     * @return array [relationPath, columnName] (e.g., ["company.address", "city"])
     */
    function getRelationDetails($columnName)
    {
        $lastDotPosition = strrpos($columnName, '.');

        $relationPath = substr($columnName, 0, $lastDotPosition);
        $relationColumnName = substr($columnName, $lastDotPosition + 1);

        return [$relationPath, $relationColumnName];
    }
}

if (! function_exists('getRelationName')) {
    /**
     * Returns the full relation path for the column specified.
     * Used for eager loading with ->with().
     *
     * @param string $columnName Name of the column (e.g., "company.address.city")
     * @return string Relation path (e.g., "company.address")
     */
    function getRelationName($columnName)
    {
        [$relationPath, $relationColumnName] = getRelationDetails($columnName);

        return $relationPath;
    }
}

if (! function_exists('getFirstRelationName')) {
    /**
     * Returns just the first relation name for foreign key selection.
     *
     * @param string $columnName Name of the column (e.g., "company.address.city")
     * @return string First relation name (e.g., "company")
     */
    function getFirstRelationName($columnName)
    {
        return strtok($columnName, '.');
    }
}

if (! function_exists('getRecordsLimit')) {
    /**
     * Returns the limit of the records to be fetched from the table.
     *
     * @param int Limit requested by the datatables
     * @return int Limit to be applied in the query
     */
    function getRecordsLimit($requestedLimit)
    {
        $maxLimit = config('laratables.max_limit');

        if (! is_int($maxLimit) || $maxLimit < 0) {
            throw new InvalidMaxLimit("Please set the 'max_limit' configuration parameter to be 0 or more.");
        }

        if ($maxLimit === 0) {
            return $requestedLimit;
        }

        if ($requestedLimit > 0) {
            return min($requestedLimit, $maxLimit);
        }

        return $maxLimit;
    }
}
