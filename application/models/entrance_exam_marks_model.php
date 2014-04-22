<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class entrance_exam_marks_model extends CI_model {

    public $mark_id;
    public $student_id;
    public $marks;
    public $create_id;
    public $create_date_time;
    public $modify_id;
    public $modify_date_time;
    private $table_name = 'entrance_exam_marks';

    function __construct() {
        parent::__construct();
    }

    function validationRules() {
        $validation_rules = array();
        return $validation_rules;
    }

    function convertObject($old) {
        $new = new entrance_exam_marks_model();
        $new->mark_id = $old->mark_id;
        $new->student_id = $old->student_id;
        $new->marks = $old->marks;
        $new->create_id = $old->create_id;
        $new->create_date_time = $old->create_date_time;
        $new->modify_id = $old->modify_id;
        $new->modify_date_time = $old->modify_date_time;
        return $new;
    }

    function toArray() {
        $arr = array();
        if ($this->mark_id != '')
            $arr['mark_id'] = $this->mark_id;

        if ($this->student_id != '')
            $arr['student_id'] = $this->student_id;

        if ($this->marks != '')
            $arr['marks'] = $this->marks;

        if ($this->create_id != '')
            $arr['create_id'] = $this->create_id;

        if ($this->create_date_time != '')
            $arr['create_date_time'] = $this->create_date_time;

        if ($this->modify_id != '')
            $arr['modify_id'] = $this->modify_id;

        if ($this->modify_date_time != '')
            $arr['modify_date_time'] = $this->modify_date_time;

        return $arr;
    }

    function getWhere($where, $limit = null, $orderby = null, $ordertype = null) {
        $objects = array();
        $this->db->select(' * ');
        $this->db->from($this->table_name);
        $this->db->where($where);
        if (is_null($orderby)) {
            $orderby = 'mark_id';
        }
        if (is_null($ordertype)) {
            $ordertype = 'desc';
        }
        $this->db->order_by($orderby, $ordertype);
        if ($limit != null) {
            $this->db->limit($limit);
        }
        $res = $this->db->get();
        if ($res->num_rows > 0) {
            return $res->result();
        } else {
            return false;
        }
    }

    function getAll($limit = null, $orderby = null, $ordertype = null) {
        $objects = array();
        $this->db->select(' * ');
        $this->db->from($this->table_name);
        if (is_null($orderby)) {
            $orderby = 'mark_id';
        }
        if (is_null($ordertype)) {
            $ordertype = 'desc';
        }
        $this->db->order_by($orderby, $ordertype);
        if ($limit != null) {
            $this->db->limit($limit);
        }
        $res = $this->db->get();
        if ($res->num_rows > 0) {
            return $res->result();
        } else {
            return false;
        }
    }

    function insertData() {
        $array = $this->toArray();
        $this->db->insert($this->table_name, $array);
        $check = $this->db->affected_rows();
        if ($check > 0) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    function updateData() {
        $array = $this->toArray();
        unset($array['mark_id']);
        $this->db->where('mark_id', $this->mark_id);
        $this->db->update($this->table_name, $array);
        return TRUE;
    }

    function deleteData() {
        $this->db->where('mark_id', $this->mark_id);
        $this->db->delete($this->table_name);
        $check = $this->db->affected_rows();
        if ($check > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getMeritList($year) {
        $sQuery = 'SELECT form_number, hall_ticket, CONCAT(firstname, " ", lastname) AS student_name, marks,(select pcb_percentage from student_edu_master where s.student_id=student_edu_master.student_id AND course ="H.S.C") AS PCB, (select pcbe_percentage from student_edu_master where s.student_id=student_edu_master.student_id AND course ="H.S.C")AS PCBE, (select total_percentage from student_edu_master where s.student_id=student_edu_master.student_id AND course ="H.S.C") AS HSC, (select total_percentage from student_edu_master where s.student_id=student_edu_master.student_id AND course ="S.S.C") AS SSC FROM student_basic_info s, entrance_exam_marks em, admission_details ad WHERE s.admission_id=ad.admission_id AND s.student_id=em.student_id AND (marks != "0.00" OR marks != null) AND ad.admission_year=' . $year . ' ORDER BY marks DESC, PCB DESC, PCBE DESC, HSC DESC, SSC DESC';
        $res = $this->db->query($sQuery);

        if ($res->num_rows > 0) {
            return $res->result();
        } else {
            return false;
        }
    }

}

?>