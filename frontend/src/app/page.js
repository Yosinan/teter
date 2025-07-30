'use client';
import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { api } from '@/utils/api';
import Link from 'next/link';

export default function Dashboard() {
    const [user, setUser] = useState(null);
    const [isLoggedIn, setIsLoggedIn] = useState(true); // Track if the user is logged in
    const router = useRouter();

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (!token) {
            setIsLoggedIn(false); // Set to false if no token found
        } else {
            const fetchUser = async () => {
                const response = await api.getUser(token);
                if (response.error) {
                    setIsLoggedIn(false); // Set to false if there's an error fetching the user
                } else {
                    setUser(response); // Set the user data if successful
                }
            };
            fetchUser();
        }
    }, [router]);

    if (!isLoggedIn) {
        return (
            <div className="flex items-center justify-center min-h-screen bg-black-50">
                <div className="bg-white p-16 rounded-lg shadow-lg w-800 text-center">
                    <h2 className="text-2xl font-semibold text-gray-800 mb-4">Welcome to Multi-User Task Management</h2>
                    <p className="text-gray-600 mb-12">Our platform allows you to manage and track your tasks efficiently with other users. Please log in to get started!</p>
                    <Link legacyBehavior href="/auth/login">
                        <a className="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-500 transition-all">
                            Log in
                        </a>
                    </Link>
                </div>
            </div>
        );
    }

    return (
        <div className="flex items-center justify-center min-h-screen bg-black-100">
            <div className="bg-white p-8 rounded-lg shadow-lg w-96">
                <div className="text-center mb-6">
                    {/* Add user avatar or placeholder */}
                    <div className="w-16 h-16 bg-blue-500 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-xl font-bold">
                        {user ? user.username[0].toUpperCase() : 'U'}
                    </div>
                    <h1 className="text-3xl font-semibold text-gray-800 mb-2">Welcome to Your Dashboard</h1>
                    <p className="text-gray-600">Hello {user ? user.username : 'User'}, here’s what’s going on!</p>
                </div>

                <div className="flex justify-center gap-4 mt-6">
                    {/* Manage Tasks Button */}
                    <Link legacyBehavior href="/tasks">
                        <a className="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-500 transition-all">
                            Manage Tasks
                        </a>
                    </Link>
                </div>
            </div>
        </div>
    );
}
