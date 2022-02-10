import { Link } from "react-router-dom";

const Header = () => {
    return (
        <header className="header">
            <div className="container clearfix">
                <div className="float-start">
                    <Link to="/">
                        <h1 className="title">Donquijote</h1>
                    </Link>
                </div>
                <div className="float-end mt-1">
                    <Link to="/menu">
                        <div className="btn btn-light">Meniu</div>
                    </Link>
                </div>
            </div>
        </header>
    );
};

export default Header;
