<?php

namespace aiprovider_mock;

class process_summarise_text extends abstract_text_processor {
    #[\Override]
    protected function build_generated_content(string $prompt): string {
        return 'Mock summary for prompt: ' . $prompt;
    }
}
