'use client';
import { useRouter } from 'next/navigation';
import { FiLogOut } from 'react-icons/fi';

export default function LogoutButton() {
    const router = useRouter();

    const handleLogout = () => {
        localStorage.removeItem('token');
        router.push('/auth/login');
    };

    return (
        <div className="flex justify-end mt-4">
            <button
                onClick={handleLogout}
                className="flex items-center text-white px-2 py-1 rounded hover:bg-red-400 transition-all"
            >
                <FiLogOut className="mr-2" /> Logout
            </button>
        </div>
    );
}
