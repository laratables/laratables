<?php

namespace Freshbitsweb\Laratables;

use Illuminate\Support\Str;

class RelationshipsManager
{
    /**
     * @var string Class with laratables methods
     */
    protected $class;

    /**
     * @var Eloquent Model object
     */
    protected $modelObject;

    /**
     * @var array Relations to be eager loaded
     */
    protected $relations = [];

    /**
     * Initialize properties.
     *
     * @param Class to customize query/data/logic
     * @param Eloquent Model object
     * @return void
     */
    public function __construct($class, $modelObject)
    {
        $this->class = $class;
        $this->modelObject = $modelObject;
    }

    /**
     * Adds the relation to be loaded with the query.
     * Supports nested relations like "company.address.city".
     *
     * @param string $columnName Name of the column
     * @return void
     */
    public function addRelation($columnName)
    {
        $relationPath = getRelationName($columnName);

        if (
            ! array_key_exists($relationPath, $this->relations) &&
            ! in_array($relationPath, $this->relations)
        ) {
            // For nested relations, convert dots to underscores for method name
            // e.g., "company.address" -> "laratablesCompanyAddressRelationQuery"
            $methodName = Str::camel('laratables_'.str_replace('.', '_', $relationPath).'_relation_query');
            if (method_exists($this->class, $methodName)) {
                $this->relations[$relationPath] = $this->class::$methodName();

                return;
            }

            $this->relations[] = $relationPath;
        }
    }

    /**
     * Returns the (foreign key) column(s) to be selected for the relation table.
     * For nested relations, only the first relation's foreign key is needed.
     *
     * @param string $columnName Name of the column
     * @return array
     */
    public function getRelationSelectColumns($columnName)
    {
        // Only the first relation matters for foreign key selection from the base model
        // For "company.address.city", we only need "company_id" from the users table
        $firstRelationName = getFirstRelationName($columnName);

        return $this->decideRelationColumns($firstRelationName);
    }

    /**
     * Decides the columns to be used based on the relationship.
     *
     * @param string Name of the relation
     * @return array
     */
    protected function decideRelationColumns($relationName)
    {
        // https://stackoverflow.com/a/25472778/3113599
        $relationType = (new \ReflectionClass($this->modelObject->$relationName()))->getShortName();
        $selectColumns = [];

        // Laravel 5.8 renamed getForeignKey() to getForeignKeyName()
        $methodName = method_exists($this->modelObject->$relationName(), 'getForeignKeyName') ?
            'getForeignKeyName' :
            'getForeignKey'
        ;

        switch ($relationType) {
            case 'BelongsTo':
                $selectColumns[] = $this->modelObject->$relationName()->{$methodName}();
                break;
            case 'MorphTo':
                $selectColumns[] = $this->modelObject->$relationName()->{$methodName}();
                $selectColumns[] = $this->modelObject->$relationName()->getMorphType();
                break;
        }

        return $selectColumns;
    }

    /**
     * Returns the relations to be loaded by query.
     *
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }
}
