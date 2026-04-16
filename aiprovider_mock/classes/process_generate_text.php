<?php

namespace aiprovider_mock;

class process_generate_text extends abstract_text_processor {
    #[\Override]
    protected function build_generated_content(string $prompt): string {
        return 'Mock response for prompt: ' . $prompt;
    }
}
