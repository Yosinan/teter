'use client';
import { useEffect, useState } from 'react';
import { api } from '@/utils/api';
import { useRouter } from 'next/navigation';
import LogoutButton from '../components/LogoutButton';
import { FiPlusCircle } from 'react-icons/fi';
import { FaCheckCircle, FaCalendarAlt, FaPen, FaTrashAlt, FaFlag } from 'react-icons/fa';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

export default function TaskList() {
    const [tasks, setTasks] = useState([]);
    const [error, setError] = useState('');
    const router = useRouter();

    const handleDelete = async (taskId) => {
        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/auth/login');
            return;
        }
        await api.deleteTask(taskId, token);
        toast.success("Task deleted successfully!");
        setTasks(tasks.filter(task => task.id !== taskId));
    };

    useEffect(() => {
        const fetchTasks = async () => {
            const token = localStorage.getItem('token');
            if (!token) {
                router.push('/auth/login');
                return;
            }
            const response = await api.getTasks(token);
            if (response.error) {
                setError(response.error);
            } else {
                setTasks(response);
            }
        };
        fetchTasks();
    }, [router]);

    return (
        <div className="p-8 max-w-5xl mx-auto">

            {/* Logout Button */}
            <LogoutButton />

            {/* Create Task Button */}
            <div className="text-center mb-8">
                <button
                    onClick={() => router.push('/tasks/create')}
                    className="flex items-center bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-500 transition-all"
                >
                    <FiPlusCircle className="mr-2" /> New Task
                </button>
            </div>

            <h2 className="text-3xl font-semibold text-left text-white mb-8">Here are your Tasks</h2>

            {error && <p className="text-red-500 text-center mb-4">{error}</p>}

            <ul>
                {tasks.map((task) => (
                    <li
                        key={task.id}
                        className="border rounded-lg shadow-lg p-5 mb-5  hover:bg-blue-50 hover:bg-opacity-10 transition-all duration-200"
                    >
                        {/* Title and Description */}
                        <h3 className="text-2xl font-semibold text-gray-200 mb-2 capitalize">{task.title}</h3>
                        <p className="text-gray-400 mb-4 capitalize">{task.description}</p>

                        {/* Task Details with Icons */}
                        <div className="grid grid-cols-4 gap-4 mb-4 text-sm text-gray-500">
                            <div className="flex items-center">
                                <FaCheckCircle className="mr-2 text-lg" />
                                <strong className='pr-1'>Status: </strong>
                                <span
                                    className={`${task.status === 'completed'
                                        ? 'text-green-600'
                                        : task.status === 'pending'
                                            ? 'text-yellow-500'
                                            : 'text-blue-500'
                                        }`}
                                >
                                    {task.status}
                                </span>
                            </div>
                            <div className="flex items-center">
                                <FaFlag className="mr-2 text-lg" />
                                <strong className='pr-1'>Priority: </strong>
                                <span
                                    className={`${task.priority === 'high'
                                        ? 'text-red-600'
                                        : task.priority === 'medium'
                                            ? 'text-yellow-600'
                                            : 'text-green-600'
                                        }`}
                                >
                                    {task.priority}
                                </span>
                            </div>
                            <div className="flex items-center">
                                <FaCalendarAlt className="mr-2 text-lg" />
                                <strong className='pr-1'>Deadline: </strong>
                                <span className="text-violet-400">{new Date(task.deadline).toLocaleDateString()}</span>
                            </div>
                        </div>

                        {/* Buttons Container */}
                        <div className="flex justify-end space-x-2">
                            {/* Edit Button */}
                            <button
                                onClick={() => router.push(`/tasks/${task.id}`)}
                                className="flex items-center bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-500 transition-all"
                            >
                                <FaPen />
                            </button>

                            {/* Delete Button */}
                            <button
                                type="button"
                                onClick={() => handleDelete(task.id)}
                                className="flex items-center bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 transition-all"
                            >
                                <FaTrashAlt />
                            </button>
                        </div>
                    </li>
                ))}
            </ul>

            <ToastContainer position="top-right" autoClose={10000} />
        </div>
    );
}
