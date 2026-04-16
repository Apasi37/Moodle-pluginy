<?php

namespace aiprovider_mock;

class process_explain_text extends abstract_text_processor {
    #[\Override]
    protected function build_generated_content(string $prompt): string {
        return 'Mock explanation for prompt: ' . $prompt;
    }
}
