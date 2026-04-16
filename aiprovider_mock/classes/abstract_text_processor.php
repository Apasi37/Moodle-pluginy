<?php

namespace aiprovider_mock;

abstract class abstract_text_processor extends \core_ai\process_base {

    #[\Override]
    protected function query_ai_api(): array {
        $prompt = trim($this->action->get_configuration('prompttext') ?? '');

        return [
            'success' => true,
            'id' => $this->generate_response_id($prompt),
            'fingerprint' => 'mock-provider',
            'generatedcontent' => $this->build_generated_content($prompt),
            'finishreason' => 'stop',
            'prompttokens' => $this->estimate_tokens($prompt),
            'completiontokens' => $this->estimate_tokens($this->build_generated_content($prompt)),
            'model' => 'mock-text-v1',
        ];
    }

    abstract protected function build_generated_content(string $prompt): string;

    private function generate_response_id(string $prompt): string {
        $time = \core\di::get(\core\clock::class)->time();

        return 'mock-' . substr(sha1($this->action::class . "|$prompt|$time"),0,16);
    }

    private function estimate_tokens(string $text): int {
        $length = strlen(trim($text));

        return $length === 0 ? 0 : max(1, (int) ceil($length / 4));
    }
}
