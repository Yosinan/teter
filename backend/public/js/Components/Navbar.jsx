import { Link } from "react-router-dom";

const Navbar = ({ user, logout }) => {
    return (
        <nav>
            <Link to="/tasks">Tasks</Link>
            {user ? (
                <button onClick={logout}>Logout</button>
            ) : (
                <>
                    <Link to="/login">Login</Link>
                    <Link to="/register">Register</Link>
                </>
            )}
        </nav>
    );
};

export default Navbar;