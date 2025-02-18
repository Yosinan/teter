'use client';
import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { api } from '@/utils/api';
import Link from 'next/link';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

export default function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const router = useRouter();

    const handleLogin = async (e) => {
        e.preventDefault();
        setError('');

        const response = await api.login({ email, password });
        if (response.error) {
            setError(response.error);
            toast.error(response.error);
        } else {
            localStorage.setItem('token', response.token);
            toast.success('Login successful!');
            router.push('/tasks');
        }
    };

    return (
        <div className="flex justify-center items-center min-h-screen">
            <form onSubmit={handleLogin} className="bg-white p-6 rounded-lg shadow-md w-96">
                <h2 className="text-2xl mb-4">Login</h2>
                {error && <p className="text-red-500">{error}</p>}
                <input
                    type="email"
                    placeholder="Email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                />
                <input
                    type="password"
                    placeholder="Password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                />
                <button type="submit" className="w-full bg-blue-500 text-white py-2 rounded">Login</button>

                {/* Don't have an account link */}
                <p className="text-center text-gray-600 mt-4">
                    Don't have an account?{' '}
                    <Link href="/auth/register" className="text-blue-500 hover:underline">
                        Register here
                    </Link>
                </p>
            </form>
            <ToastContainer />
        </div>
    );
}
