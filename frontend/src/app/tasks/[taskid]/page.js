'use client';
import { useEffect, useState } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { api } from '@/utils/api';
import { FaArrowLeft } from 'react-icons/fa';
import { ToastContainer, toast } from 'react-toastify'; // Import Toastify
import 'react-toastify/dist/ReactToastify.css'; // Import Toastify CSS

export default function UpdateTask() {
    const { taskid } = useParams(); // Getting taskid from the route
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [status, setStatus] = useState('');
    const [priority, setPriority] = useState('');
    const [deadline, setDeadline] = useState('');
    const [error, setError] = useState('');
    const router = useRouter();

    useEffect(() => {
        const fetchTask = async () => {
            const token = localStorage.getItem('token');
            if (!token) {
                router.push('/auth/login');
                return;
            }
            const response = await api.getTasks(token);

            const task = response.find((task) => String(task.id) === String(taskid));
            if (task) {
                setTitle(task.title);
                setDescription(task.description);
                setStatus(task.status || 'pending'); // Default to 'pending' if no status is found
                setPriority(task.priority || 'low'); // Default to 'low' if no priority is found
                setDeadline(task.deadline); // Assuming the date is in a valid format
            } else {
                setError('Task not found');
            }
        };
        fetchTask();
    }, [taskid, router]);

    const handleUpdate = async (e) => {
        e.preventDefault();
        setError('');
        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/auth/login');
            return;
        }
        const updatedTask = { title, description, status, priority, deadline };
        const response = await api.updateTask(taskid, updatedTask, token);
        if (response.error) {
            setError(response.error);
            toast.error(response.error); // Show error toast
        } else {
            toast.success('Task updated successfully!'); // Show success toast
            router.push('/tasks');
        }
    };

    const handleDelete = async () => {
        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/auth/login');
            return;
        }
        await api.deleteTask(taskid, token);
        toast.success('Task deleted successfully!'); // Show success toast for deletion
        router.push('/tasks');
    };

    const handleGoBack = () => {
        router.back();
    };

    return (
        <div className="flex justify-center items-center min-h-screen">
            <form onSubmit={handleUpdate} className="bg-white p-6 rounded-lg shadow-md w-96">
                <h2 className="text-2xl mb-4">Update Task</h2>
                {error && <p className="text-red-500">{error}</p>}

                {/* Go Back Button */}
                <button
                    type="button"
                    onClick={handleGoBack}
                    className="flex items-center text-gray-500 hover:text-gray-700 text-sm mb-4"
                >
                    <FaArrowLeft className="mr-2" /> Go Back
                </button>

                {/* Title Field */}
                <input
                    type="text"
                    placeholder="Title"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                />

                {/* Description Field */}
                <textarea
                    placeholder="Description"
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                />

                {/* Status Field */}
                <select
                    value={status}
                    onChange={(e) => setStatus(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                >
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>

                {/* Priority Field */}
                <select
                    value={priority}
                    onChange={(e) => setPriority(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                >
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

                {/* Deadline Field */}
                <input
                    type="date"
                    value={deadline}
                    onChange={(e) => setDeadline(e.target.value)}
                    className="w-full p-2 mb-2 border rounded"
                />

                {/* Update Button */}
                <button type="submit" className="w-full bg-blue-500 text-white py-2 rounded mt-4">Update Task</button>

            </form>

            {/* Toast Container */}
            <ToastContainer position="top-right" autoClose={15000} />
        </div>
    );
}
