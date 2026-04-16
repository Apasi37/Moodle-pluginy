
import {getString} from 'core/str';

export const init = async (ajaxurl, initialHistory = []) => {
    const form = document.querySelector('#block-ai-helper-form');
    const promptInput = document.querySelector('#id_ai_helper_prompt');
    const resultBox = document.querySelector('#ai_helper_result');

    if (!form || !promptInput || !resultBox) {
        return;
    }

    const errorString = await getString('erroroccurred', 'block_ai_helper', '');

    promptInput.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit', {cancelable: true}));
            promptInput.value = '';
        }
    });

    if (initialHistory.length) {
        renderHistory(initialHistory, resultBox);
    }

    form.addEventListener('submit', e => {
        e.preventDefault();

        const prompt = promptInput.value.trim();
        if (!prompt) {
            return;
        }

        resultBox.innerHTML += `
            <div class="ai-message user">${prompt}</div>
            <div class="ai-message bot">...</div>
        `;
        resultBox.scrollTop = resultBox.scrollHeight;

        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: new URLSearchParams({
                prompt: prompt,
                sesskey: M.cfg.sesskey,
            }),
        })
        .then(response => {
            if (!response.ok) {
                throw Error(response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderHistory(data.history || [], resultBox);
                promptInput.value = '';
            } else {
                resultBox.innerHTML += `
                    <div class="ai-message bot">${errorString}${data.error || ''}</div>
                `;
            }
        })
        .catch(error => {
            resultBox.innerHTML += `
                <div class="ai-message bot">${errorString}${error.message}</div>
            `;
        });
    });
};

const renderHistory = (history, container) => {
    container.innerHTML = '';

    history.forEach(item => {
        container.innerHTML += `
            <div class="ai-message user">${item.prompt}</div>
            <div class="ai-message bot">${item.response}</div>
        `;
    });

    container.scrollTop = container.scrollHeight;
};