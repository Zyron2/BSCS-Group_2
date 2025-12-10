# AI Assistant Chatbot Setup Guide

This guide will help you set up the AI Assistant chatbot powered by Ollama in your Laravel application.

## Prerequisites

1. **Ollama Installation**: Download and install Ollama from [https://ollama.ai](https://ollama.ai)
2. **PHP 8.2+** with Laravel 12
3. **Composer** for dependency management

## Installation Steps

### 1. Install Ollama

Download and install Ollama for your operating system:
- **Windows**: Download from [ollama.ai/download](https://ollama.ai/download)
- **Mac**: `brew install ollama`
- **Linux**: `curl -fsSL https://ollama.ai/install.sh | sh`

### 2. Pull AI Models

After installing Ollama, pull the models you want to use:

```bash
# Pull the Llama 2 model (default)
ollama pull llama2

# Or pull other models:
ollama pull mistral
ollama pull codellama
ollama pull phi
```

### 3. Start Ollama Service

Start the Ollama service:

```bash
ollama serve
```

The service will run on `http://localhost:11434` by default.

### 4. Configure Laravel Environment

If you don't have a `.env` file, copy from the example:

```bash
cp .env.example .env
```

Add the following configuration to your `.env` file:

```env
# Ollama AI Configuration
OLLAMA_API_URL=http://localhost:11434
```

### 5. Clear Laravel Cache

```bash
php artisan config:clear
php artisan cache:clear
```

## Usage

### Accessing the Chat Interface

1. Log in to your application
2. Navigate to the **AI Assistant** link in the navigation menu
3. Start chatting with the AI!

### Available Routes

- `GET /chat` - Display chat interface
- `POST /chat/send` - Send a message to the AI
- `GET /chat/models` - Get list of available Ollama models
- `POST /chat/stream` - Stream chat responses (for real-time updates)

## Features

- 🤖 **Multiple AI Models**: Switch between different Ollama models
- 💬 **Real-time Chat**: Interactive chat interface
- 🌙 **Dark Mode Support**: Seamless integration with app theme
- 📱 **Responsive Design**: Works on desktop and mobile devices
- 🔒 **Authentication Required**: Only logged-in users can access

## Troubleshooting

### Error: "Could not connect to AI assistant"

**Solution**: Make sure Ollama is running:
```bash
ollama serve
```

### Error: "No response from AI"

**Solution**: Check if the model is installed:
```bash
ollama list
```

If the model is missing, pull it:
```bash
ollama pull llama2
```

### Error: "Connection refused"

**Solution**: Verify the Ollama API URL in your `.env` file matches the running service:
```env
OLLAMA_API_URL=http://localhost:11434
```

### Slow Response Times

**Solution**: 
- Use smaller models like `phi` or `mistral` for faster responses
- Ensure your system has enough RAM (8GB+ recommended)
- Close other resource-intensive applications

## Customization

### Change Default Model

Edit `ChatController.php` and change the default model:

```php
$model = $request->input('model', 'llama2'); // Change 'llama2' to your preferred model
```

### Adjust Timeout

If you're experiencing timeout errors with large prompts, increase the timeout in `ChatController.php`:

```php
$response = Http::timeout(120)->post(...); // Increase from 120 to higher value
```

### Custom Styling

The chat interface uses Tailwind CSS. Modify `resources/views/chat/index.blade.php` to customize the appearance.

## Available Ollama Models

Some popular models you can use:

- **llama2** (7B) - General-purpose, balanced model
- **mistral** (7B) - Fast and efficient
- **codellama** (7B) - Optimized for code generation
- **phi** (2.7B) - Small and fast
- **vicuna** (7B) - Conversational AI
- **neural-chat** (7B) - Enhanced conversation abilities

List all available models:
```bash
ollama list
```

## API Endpoints

### Send Message
```
POST /chat/send
Content-Type: application/json

{
    "message": "Your question here",
    "model": "llama2"
}
```

### Get Models
```
GET /chat/models
```

## Security Considerations

- Chat routes are protected by authentication middleware
- CSRF protection is enabled on all POST requests
- User input is escaped to prevent XSS attacks
- Consider rate limiting for production environments

## Performance Tips

1. **Use GPU acceleration** if available (Ollama supports CUDA and Metal)
2. **Run Ollama on a dedicated server** for better performance in production
3. **Implement caching** for common queries
4. **Add rate limiting** to prevent abuse
5. **Use smaller models** for faster responses

## Production Deployment

For production environments:

1. **Run Ollama as a service** on a dedicated server
2. **Update OLLAMA_API_URL** to point to your Ollama server
3. **Add rate limiting** middleware
4. **Implement logging** for chat interactions
5. **Consider using Redis** for session management
6. **Monitor resource usage** (CPU, RAM, GPU)

## Support

For issues related to:
- **Ollama**: Visit [github.com/ollama/ollama](https://github.com/ollama/ollama)
- **This Implementation**: Check the application's documentation

## License

This chatbot integration is part of the Campus Coord application.

---

**Note**: Make sure Ollama is always running when using the AI Assistant feature. For production, consider using a process manager like systemd or supervisor to keep Ollama running continuously.
