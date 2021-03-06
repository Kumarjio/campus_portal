<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class counselling extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->admin_layout->setLayout('template/layout_admission');


        $session = $this->session->userdata('admin_session');
        if (empty($session) || $session->type != 'admission') {
            $this->session->set_flashdata('error', 'Login First');
            redirect(base_url() . 'login', 'refresh');
        }

        $this->load->model('entrance_exam_marks_model', 'eemm');
        $this->load->model('courses_model');
        $this->load->model('student_basic_info_model');
        $this->load->model('studnet_images_model');
        $this->load->model('admission_details_model');
        $this->load->model('admission_candidate_status_model', 'acsm');
        $this->load->model('student_basic_ug_details_model');
        $this->load->model('student_basic_pg_details_model');
        $this->load->model('student_basic_pg_other_details_model');
        $this->load->model('course_specialization_model');
    }

    public function index() {
        $this->admin_layout->setField('page_title', 'Student Counselling');
        $data['basic_info'] = $this->student_basic_info_model->getWhere(array('student_id' => $this->input->post('student_id')));
        $this->admin_layout->view('admission/counselling/index', $data);
    }

    function getStudentList() {
        $res = $this->student_basic_info_model->getStudentDetails($_GET['term'], 3);
        $customers = array();
        foreach ($res as $r) {
            $temp = array();
            $temp['value'] = $r->student_id;
            $temp['label'] = $r->firstname . ' ' . $r->lastname . ' (' . $r->form_number . ')';
            $customers[] = $temp;
        }
        echo json_encode($customers);
    }

    function getStudentHistory($student_id) {
        $this->admin_layout->setField('page_title', 'Student History');
        $data['basic_info'] = $this->student_basic_info_model->getWhere(array('student_id' => $student_id));

        if ($data['basic_info'][0]->status == 3) {
            $data['student_id'] = $student_id;

            if ($data['basic_info'][0]->degree == 'UG') {
                $data['course_details'] = $this->courses_model->getWhere(array('degree' => 'UG', 'status' => 'A'));
                $data['basic_details'] = $this->student_basic_ug_details_model->getWhere(array('student_id' => $student_id));
            }

            if ($data['basic_info'][0]->degree == 'PG_OTHER' || $data['basic_info'][0]->degree == 'Certificate') {
                $data['course_details'] = $this->courses_model->getPgCourse(array('PG', 'Certificate'), 'N');
                $data['basic_details'] = $this->student_basic_pg_other_details_model->getWhere(array('student_id' => $student_id));
            }

            if ($data['basic_info'][0]->degree == 'PG' || $data['basic_info'][0]->degree == 'SS' || $data['basic_info'][0]->degree == 'Diploma') {
                $data['course_details'] = $this->courses_model->getPgCourse(array('PG', 'SS', 'Diploma'), 'Y');
                $data['basic_details'] = $this->student_basic_pg_details_model->getWhere(array('student_id' => $student_id));
            }

            $data['course_specialization'] = $this->course_specialization_model->getWhere(array('course_id' => $data['basic_info'][0]->course_id));
            $data['candidate_status_info'] = $this->acsm->getWhere(array('status' => 'A'), NULL, NULL, 'ASC');
            $data['merit_info'] = $this->eemm->getWhere(array('student_id' => $student_id));
            $data['image_details'] = $this->studnet_images_model->getWhere(array('student_id' => $student_id));

            echo $this->load->view('admission/counselling/student_history', $data, true);
        } else {
            $this->session->set_flashdata('error', 'Student Status is not Set to Counselling');
            redirect(ADMISSION_URL . 'counselling', 'refresh');
        }
    }

    function updateStudentDetails($student_id) {
        $obj = new student_basic_info_model();
        $basic_info = $obj->getWhere(array('student_id' => $student_id));
        if ($basic_info[0]->status == 3) {
            if ($this->input->post('course_id') != $basic_info[0]->course_id) {
                $course = $this->courses_model->getWhere(array('course_id' => $this->input->post('course_id')));
                $obj->form_number = substr($basic_info[0]->form_number, 0, 4) . $course[0]->short_code . substr($basic_info[0]->form_number, 6);
                $obj->course_id = $this->input->post('course_id');
            }

            $obj->status = $this->input->post('admission_status_id');
            $session = $this->session->userdata('admin_session');
            $obj->modify_id = $session->admin_id;
            $obj->modify_date_time = get_current_date_time()->get_date_time_for_db();
            $obj->student_id = $student_id;
            $obj->updateData();

            if ($basic_info[0]->degree == 'PG_OTHER' || $basic_info[0]->degree == 'Certificate') {
                $obj_detail = new student_basic_pg_other_details_model();
                $obj_detail->student_id = $student_id;
                $obj_detail->course_special_id = $this->input->post('preference_1');
                $obj_detail->preference_1 = $this->input->post('preference_1');
                $obj_detail->preference_2 = $this->input->post('preference_2');
                $obj_detail->preference_3 = $this->input->post('preference_3');
                $obj_detail->updateData();
            } else  if ($basic_info[0]->degree == 'PG' || $basic_info[0]->degree == 'SS' || $basic_info[0]->degree == 'Diploma') {
                $obj_detail = new student_basic_pg_details_model();
                $obj_detail->student_id = $student_id;
                $obj_detail->course_special_id = $this->input->post('preference_1');
                $obj_detail->preference_1 = $this->input->post('preference_1');
                $obj_detail->preference_2 = $this->input->post('preference_2');
                $obj_detail->preference_3 = $this->input->post('preference_3');
                $obj_detail->updateData();
            }

            $this->session->set_flashdata('success', 'Admission Order is Issue');
            redirect(ADMISSION_URL . 'counselling', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Student Status is not Set to Counselling');
            redirect(ADMISSION_URL . 'counselling', 'refresh');
        }
    }

}
