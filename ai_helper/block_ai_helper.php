<?php

defined('MOODLE_INTERNAL') || die();

class block_ai_helper extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_ai_helper');
    }

    public function applicable_formats() {
        return array('all' => true);
    }

    public function has_config() {
        return false;
    }

    public function specialization() {
        if (!empty($this->config->title)) {
            $this->title = $this->config->title;
        }
    }

    public function get_content() {
        global $PAGE, $SESSION;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $history = $SESSION->ai_helper_history ?? [];

        $ajaxurl = new moodle_url('/blocks/ai_helper/ajax.php');
        $PAGE->requires->js_call_amd('block_ai_helper/block', 'init', [$ajaxurl->out(false),$history]);

        $label = get_string('promptlabel', 'block_ai_helper');
        $submit = get_string('submitbutton', 'block_ai_helper');

        $form = '
        <div class="ai-helper-container">

            <div id="ai_helper_result" class="ai_helper_response"></div>

            <form id="block-ai-helper-form" method="post" class="ai-helper-form">
                <textarea 
                    name="ai_helper_prompt" 
                    id="id_ai_helper_prompt" 
                    rows="3" 
                    placeholder="' . s(get_string('promptlabel', 'block_ai_helper')) . '"
                ></textarea>

                <input type="hidden" name="sesskey" value="' . sesskey() . '">

                <button type="submit">
                    ' . s(get_string('submitbutton', 'block_ai_helper')) . '
                </button>
            </form>

        </div>
        ';

        $this->content->text = $form;
        $this->content->footer = '';

        return $this->content;
    }

}