
class DesaAIAssistant {
    constructor(options) {
        this.apiUrl = options.apiUrl || '';
        this.apiKey = window.DESA_THEME_LICENSE_KEY || '';
        this.villageUrl = options.villageUrl || '';
        this.storageKey = 'desa_ai_history_' + btoa(this.villageUrl).replace(/[^a-zA-Z0-9]/g, '');
        this.conversationHistory = this.loadHistory();
        this.abortController = null;
        this.isStreaming = false;
    }

    loadHistory() {
        try {
            const stored = sessionStorage.getItem(this.storageKey);
            return stored ? JSON.parse(stored) : [];
        } catch (e) {
            console.warn('Failed to load chat history:', e);
            return [];
        }
    }

    saveHistory() {
        try {
            // Prune if too long before saving
            if (this.conversationHistory.length > 20) {
                this.conversationHistory = this.conversationHistory.slice(-20);
            }
            sessionStorage.setItem(this.storageKey, JSON.stringify(this.conversationHistory));
        } catch (e) {
            console.warn('Failed to save chat history:', e);
        }
    }

    /**
     * Abort the current streaming response
     */
    abort() {
        if (this.abortController) {
            this.abortController.abort();
            this.abortController = null;
        }
        this.isStreaming = false;
    }

    /**
     * Send a message to the AI assistant
     * @param {string} message - The user's message
     * @param {function} onChunk - Callback for each response chunk
     * @param {function} onComplete - Callback when response is complete
     * @param {function} onError - Callback for errors
     */
    async sendMessage(message, onChunk, onComplete = null, onError = null) {
        // Abort any existing stream
        this.abort();

        this.abortController = new AbortController();
        this.isStreaming = true;

        try {
            const response = await fetch(`${this.apiUrl}/api/chat`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-API-Key': this.apiKey
                },
                body: JSON.stringify({
                    village_url: this.villageUrl,
                    message: message,
                    conversation_history: this.conversationHistory,
                    stream: true
                }),
                signal: this.abortController.signal
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'Request failed');
            }

            // Handle SSE stream
            const reader = response.body.getReader();
            const decoder = new TextDecoder();
            let fullResponse = '';

            while (true) {
                const { done, value } = await reader.read();
                if (done) break;

                const chunk = decoder.decode(value);
                const lines = chunk.split('\n');

                for (const line of lines) {
                    if (line.startsWith('data: ')) {
                        try {
                            const data = JSON.parse(line.slice(6));

                            if (data.content) {
                                fullResponse += data.content;
                                if (onChunk) onChunk(data.content);
                            }

                            if (data.done) {
                                // Add to conversation history
                                this.conversationHistory.push(
                                    { role: 'user', content: message },
                                    { role: 'assistant', content: fullResponse }
                                );

                                // Keep history limited
                                if (this.conversationHistory.length > 20) {
                                    this.conversationHistory = this.conversationHistory.slice(-20);
                                }

                                this.saveHistory();

                                if (onComplete) onComplete(fullResponse, data.cached, data.suggestions);
                            }
                        } catch (e) {
                            console.warn('Failed to parse SSE data:', e);
                        }
                    }
                }
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                // Stream was intentionally aborted - not an error
                console.log('Stream aborted by user');
                return;
            }
            console.error('DesaAIAssistant error:', error);
            if (onError) onError(error);
        } finally {
            this.isStreaming = false;
            this.abortController = null;
        }
    }

    /**
     * Send a message and get a non-streaming response
     * @param {string} message - The user's message
     * @returns {Promise<object>} - The response object
     */
    async sendMessageSync(message) {
        const response = await fetch(`${this.apiUrl}/api/chat`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-API-Key': this.apiKey
            },
            body: JSON.stringify({
                village_url: this.villageUrl,
                message: message,
                conversation_history: this.conversationHistory,
                stream: false
            })
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.error || 'Request failed');
        }

        const data = await response.json();

        // Add to conversation history
        this.conversationHistory.push(
            { role: 'user', content: message },
            { role: 'assistant', content: data.response }
        );
        this.saveHistory();

        return data;
    }

    /**
     * Clear the conversation history
     */
    clearHistory() {
        this.conversationHistory = [];
        try {
            sessionStorage.removeItem(this.storageKey);
        } catch (e) {
            console.warn('Failed to clear chat history:', e);
        }
    }

    /**
     * Clear the server-side cache for this village
     */
    async clearCache() {
        const response = await fetch(
            `${this.apiUrl}/api/cache/${encodeURIComponent(this.villageUrl)}`,
            {
                method: 'DELETE',
                headers: {
                    'X-API-Key': this.apiKey
                }
            }
        );

        return response.json();
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DesaAIAssistant;
}
