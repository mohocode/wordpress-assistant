<?php

namespace App\DB;

class WordpressDb {

    protected static $wpdb;
    protected static $table;
    protected static $whereConditions = [];
    protected static $selectColumns = [];
    protected static $orderBy = [];
    protected static $limit;
    protected static $offset;

    public static function init() {
        global $wpdb;
        self::$wpdb = $wpdb;
    }

    public static function table($table) {
        self::$table = $table;
        return new self();
    }

    public static function orderBy($column, $direction = 'asc') {
        self::$orderBy = [$column, $direction];
        return new self();
    }

    public static function take($limit) {
        self::$limit = $limit;
        return new self();
    }

    public static function deleteByCustomColumn($column, $value) {
        return self::$wpdb->delete(self::$table, array($column => $value));
    }

    public static function deleteByID($id) {
        return self::$wpdb->delete(self::$table, array('ID' => $id));
    }

    public static function getFirstRecord() {
        return self::$wpdb->get_row("SELECT * FROM " . self::$table . " LIMIT 1");
    }

    public static function getRecordsAsArray() {
        return self::$wpdb->get_results("SELECT * FROM " . self::$table, ARRAY_A);
    }

    public static function getRecordsAsObject() {
        return self::$wpdb->get_results("SELECT * FROM " . self::$table);
    }

    public static function updateRecord($column, $value, $data) {
        return self::$wpdb->update(self::$table, $data, array($column => $value));
    }

    public static function getAllData() {
        return self::$wpdb->get_results("SELECT * FROM " . self::$table);
    }

    public static function when($column, $value) {
        self::$whereConditions[$column] = $value;
        return new self();
    }

    public static function whenExists() {
        $whereClause = self::buildWhereClause();
        return self::$wpdb->get_results("SELECT * FROM " . self::$table . " $whereClause");
    }

    public static function whenNotExists() {
        $whereClause = self::buildWhereClause();
        return self::$wpdb->get_results("SELECT * FROM " . self::$table . " WHERE NOT EXISTS (SELECT 1 FROM " . self::$table . " $whereClause)");
    }

    public static function paginate($perPage, $page = 1) {
        $offset = ($page - 1) * $perPage;
        $whereClause = self::buildWhereClause();
        $query = "SELECT * FROM " . self::$table . " $whereClause LIMIT $perPage OFFSET $offset";
        return self::$wpdb->get_results($query);
    }

    public static function count() {
        $whereClause = self::buildWhereClause();
        return self::$wpdb->get_var("SELECT COUNT(*) FROM " . self::$table . " $whereClause");
    }

    public static function max($column) {
        return self::$wpdb->get_var("SELECT MAX($column) FROM " . self::$table);
    }

    public static function avg($column) {
        return self::$wpdb->get_var("SELECT AVG($column) FROM " . self::$table);
    }

    public static function doesntExist() {
        $whereClause = self::buildWhereClause();
        return !self::$wpdb->get_var("SELECT COUNT(*) FROM " . self::$table . " $whereClause");
    }

    public static function select(...$columns) {
        self::$selectColumns = $columns;
        return new self();
    }

    public static function where($column, $operator, $value) {
        self::$whereConditions[$column] = [$operator, $value];
        return new self();
    }

    protected static function buildWhereClause() {
        $where = '';
        foreach (self::$whereConditions as $column => $condition) {
            if (is_array($condition)) {
                $operator = $condition[0];
                $value = $condition[1];
                $where .= " AND $column $operator '$value'";
            } else {
                $where .= " AND $column = '$condition'";
            }
        }
        return $where ? ' WHERE ' . ltrim($where, ' AND') : '';
    }

    public static function get() {
        $select = !empty(self::$selectColumns) ? implode(', ', self::$selectColumns) : '*';
        $whereClause = self::buildWhereClause();
        $orderByClause = !empty(self::$orderBy) ? " ORDER BY " . self::$orderBy[0] . " " . self::$orderBy[1] : '';
        $limitClause = isset(self::$limit) ? " LIMIT " . self::$limit : '';
        $query = "SELECT $select FROM " . self::$table . "$whereClause$orderByClause$limitClause";
        return self::$wpdb->get_results($query);
    }

   
}
