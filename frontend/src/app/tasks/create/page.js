'use client';
import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { api } from '@/utils/api';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { FaArrowLeft } from 'react-icons/fa';

export default function CreateTask() {
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [status, setStatus] = useState('pending'); // Default to 'pending'
    const [priority, setPriority] = useState('low'); // Default to 'low'
    const [deadline, setDeadline] = useState('');
    const [error, setError] = useState('');
    const router = useRouter();

    const handleCreate = async (e) => {
        e.preventDefault();
        setError('');
        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/auth/login');
            return;
        }

        const taskData = { title, description, status, priority, deadline };

        try {
            const response = await api.createTask(taskData, token);
            if (response.error) {
                setError(response.error);
                toast.error(response.error);
            } else {
                toast.success('Task created successfully!');
                router.push('/tasks');
            }
        } catch (err) {
            setError('An error occurred while creating the task.');
            toast.error('An error occurred while creating the task.');
        }
    };

    const handleGoBack = () => {
        router.back();
    };

    return (
        <div className="flex justify-center items-center min-h-screen">
            <form onSubmit={handleCreate} className="bg-white p-6 rounded-lg shadow-md w-96">
                {/* Go Back Button */}
                <button
                    type="button"
                    onClick={handleGoBack}
                    className="flex items-center text-gray-500 hover:text-gray-700 text-sm mb-4"
                >
                    <FaArrowLeft className="mr-2" /> Go Back
                </button>

                <h2 className="text-2xl mb-4">Create Task</h2>
                {error && <p className="text-red-500">{error}</p>}

                <input
                    type="text"
                    placeholder="Title"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                    required
                />

                <textarea
                    placeholder="Description"
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                />

                <input
                    type="date"
                    value={deadline}
                    onChange={(e) => setDeadline(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                    required
                />

                <select
                    value={status}
                    onChange={(e) => setStatus(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                >
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="in_progress">In Progress</option>
                </select>

                <select
                    value={priority}
                    onChange={(e) => setPriority(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                >
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

                <button type="submit" className="w-full bg-green-500 text-white py-2 rounded">
                    Create Task
                </button>
            </form>

            <ToastContainer position="top-right" autoClose={5000} />
        </div>
    );
}
