import axios from "axios";
import { useContext } from "react";
import { Link } from "react-router-dom";
import { AppContext } from "../contexts/AppContext";

const Menu = () => {
    const { loggedIn, setLoggedIn } = useContext(AppContext);

    const connectOptions = () => {
        return (
            <div className="menu">
                <div className="mt-3">
                    <Link to="/sign_in">
                        <div className="btn btn-success">Conecteză-te</div>
                    </Link>
                </div>
                <div className="mt-3">sau</div>
                <div className="mt-3">
                    <Link to="/sign_up">
                        <div className="btn btn-secondary">Creaază Cont</div>
                    </Link>
                </div>
            </div>
        );
    };

    const handleLogout = () => {
        axios.post("/logout").then(() => {
            setLoggedIn(false);
        });
    };

    const loggedInMenu = () => {
        return (
            <div className="menu">
                <div className="mt-3">
                    <Link to="/games">
                        <div className="btn btn-success">Joacă</div>
                    </Link>
                </div>
                <div className="mt-3">
                    <Link to="/history">
                        <div className="btn btn-outline-dark">Istoric Jocuri</div>
                    </Link>
                </div>
                <div className="mt-3">
                    <Link to="/tutorial">
                        <div className="btn btn-outline-dark">Regulile Jocului</div>
                    </Link>
                </div>
                <div className="mt-3">
                    <div className="btn btn-danger" onClick={handleLogout}>
                        Deloghează-te
                    </div>
                </div>
            </div>
        );
    };

    return (
        <div className="text-center">
            <h1>Meniu</h1>
            {loggedIn ? loggedInMenu() : connectOptions()}
        </div>
    );
};

export default Menu;
