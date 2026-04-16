# Moodle-pluginy

<h3>Mock Provider (<code>aiprovider_mock</code>)</h3>
<p>
  The <code>aiprovider_mock</code> plugin is a testing implementation of an AI provider
  that simulates responses without calling any external API. It follows the same interface
  as real providers, but returns predefined or deterministic outputs. This makes it useful
  for testing integration, debugging features, and avoiding API costs during development.
</p>

<h3>Block Plugin (<code>ai_helper</code>)</h3>
<p>
  The <code>ai_helper</code> plugin is a Moodle UI component that provides a simple
  chat-like interface for interacting with AI. Users can enter prompts and receive
  generated responses directly within the LMS. It connects to the AI subsystem, whether
  using a real provider or the mock provider, and demonstrates how AI features can be
  integrated into the user interface.
</p>
