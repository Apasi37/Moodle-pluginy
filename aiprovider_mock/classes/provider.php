<?php

namespace aiprovider_mock;

class provider extends \core_ai\provider {

    public static function get_action_list(): array {
        return [
            \core_ai\aiactions\generate_text::class,
            \core_ai\aiactions\summarise_text::class,
            \core_ai\aiactions\explain_text::class,
        ];
    }

    public function is_provider_configured(): bool {
        return true;
    }
}
