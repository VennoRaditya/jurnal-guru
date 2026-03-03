<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | Secure Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a;
            color: #ededed;
        }

        .mesh-gradient {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 50% -20%, #1e293b 0%, transparent 50%),
                radial-gradient(circle at 0% 100%, #0f172a 0%, transparent 50%);
            z-index: -1;
        }

        .auth-card {
            background: rgba(18, 18, 18, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .input-field {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s ease;
        }

        .input-field:focus {
            border-color: #3b82f6;
            background: rgba(255, 255, 255, 0.05);
            outline: none;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background: #ffffff;
            color: #000000;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #e2e2e2;
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Subtle Fade In */
        .animate-subtle {
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="mesh-gradient"></div>

    <div class="w-full max-w-[400px] animate-subtle">
        {{-- Branding Area --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-white/5 border border-white/10 mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-xl font-semibold tracking-tight">Admin Authentication</h1>
            <p class="text-sm text-zinc-500 mt-1">Please enter your credentials to continue.</p>
        </div>

        {{-- Main Card --}}
        <div class="auth-card rounded-2xl p-8">
            
            @if(session('error'))
                <div class="mb-6 p-3 bg-red-500/10 border border-red-500/20 rounded-lg flex items-center gap-3">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-xs font-medium text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-medium text-zinc-400 mb-2">Email Address</label>
                    <input type="email" name="email" placeholder="name@company.com" required 
                        class="input-field w-full rounded-lg px-4 py-3 text-sm">
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-medium text-zinc-400">Password</label>
                    </div>
                    <input type="password" name="password" placeholder="••••••••" required 
                        class="input-field w-full rounded-lg px-4 py-3 text-sm">
                </div>

                <button type="submit" class="btn-primary w-full py-3 rounded-lg text-sm font-semibold mt-2">
                    Sign In to Dashboard
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-white/5 text-center">
                <a href="{{ route('login') }}" class="text-xs text-zinc-500 hover:text-white transition-colors flex items-center justify-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Return to Staff Portal
                </a>
            </div>
        </div>

        {{-- Footer Info --}}
        <div class="mt-8 text-center flex items-center justify-center gap-4 opacity-30">
            <span class="text-[10px] font-medium tracking-widest uppercase">Encrypted Session</span>
            <span class="w-1 h-1 bg-zinc-500 rounded-full"></span>
            <span class="text-[10px] font-medium tracking-widest uppercase">System v2.4</span>
        </div>
    </div>

</body>
</html>