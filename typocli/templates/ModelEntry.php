    /**
    * {{columnName}}
    *
    * @var {{columnType}}
    */
    protected ${{columnName}} = {{defaultValue}};

    /**
    * Returns the {{columnName}}
    *
    * @return {{columnType}} ${{columnName}}
    */
    public function get{{ColumnName}}() {
        return $this->{{columnName}};
    }

    /**
    * Sets the {{columnName}}
    *
    * @param {{columnType}} ${{columnName}}
    * @return void
    */
    public function set{{ColumnName}}(${{columnName}}) {
        $this->{{columnName}} = ${{columnName}};
    }