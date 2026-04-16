<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

require_once(__DIR__ . '/../../config.php');
require_login();
require_sesskey();

$prompt = required_param('prompt', PARAM_RAW_TRIMMED);

$response = ['success' => false];

if (!class_exists('\\local_ai_gateway\\manager')) {
    $response['error'] = get_string('aigatewaynotavailable', 'block_ai_helper');
} else {
    try {
        global $USER, $SESSION;

        $manager = \core\di::get(\core_ai\manager::class);
        $context = \context_system::instance();

        if (!$manager->is_action_available(\core_ai\aiactions\generate_text::class)) {
            throw new \moodle_exception('errorproviderunavailable', 'block_ai_helper');
        }

        $action = new \core_ai\aiactions\generate_text(
            contextid: $context->id,
            userid: $USER->id,
            prompttext: $prompt,
        );

        $result = $manager->process_action($action);

        if (!$result->get_success()) {
            throw new \moodle_exception('errorproviderunavailable', 'block_ai_helper','', null, $response->get_errormessage());
        }

        $data = $result->get_response_data();

        if (empty($data['generatedcontent'])) {
            throw new \moodle_exception('errorproviderunavailable', 'block_ai_helper','', null, 'Empty response from Moodle AI subsystem.');
        }

        $text = $data['generatedcontent'];

        $SESSION->ai_helper_history[] = [
            'prompt' => $prompt,
            'response' => $text
        ];

        // Limit history (last 10)
        if (count($SESSION->ai_helper_history) > 10) {
            array_shift($SESSION->ai_helper_history);
        }

        $response['success'] = true;
        $response['history'] = $SESSION->ai_helper_history;
    } catch (\Throwable $e) {
        $response['error'] = $e->getMessage();
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit();
