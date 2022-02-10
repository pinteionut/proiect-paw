import axios from "axios";
import { useContext, useState } from "react";
import { AppContext } from "../contexts/AppContext";
import { useNavigate } from 'react-router-dom';

const SignIn = () => {
    const navigate = useNavigate();
    const { loggedIn, setLoggedIn } = useContext(AppContext);

    const [state, setState] = useState({
        email: "",
        password: "",
        errors: {},
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        axios
            .post("/login", {
                email: state.email,
                password: state.password
            })
            .then(() => {
                setLoggedIn(true);
                navigate('/menu');
            })
            .catch((response) => {
                setState({
                    ...state,
                    errors: JSON.parse(response.request.responseText).errors,
                });
            });
    };

    const errors = () => {
        const errorKeys = Object.keys(state.errors);
        if (errorKeys.length == 0) return null;
        return (
            <div className="bg-light mt-3 p-3 border border-danger">
                {errorKeys.map((errorKey) => {
                  return state.errors[errorKey].map((error, index) => {
                    return <div key={`${errorKey}-${index}`} className="text-danger">{error}</div>
                  })
                })}
            </div>
        );
    };

    return (
        <form>
            <h1 className="text-center">Conectare</h1>
            {
                loggedIn ? <h6 className="text-center mt-3">Ești deja conectat.</h6>: (
                    <>
                    <div className="form-group mt-3">
                        <label>Adresa de email</label>
                        <input
                            type="email"
                            className="form-control"
                            placeholder="Introdu adresa de email"
                            onChange={(e) =>
                                setState({ ...state, email: e.target.value })
                            }
                            required={true}
                        />
                    </div>
                    <div className="form-group mt-3">
                        <label>Parola</label>
                        <input
                            type="password"
                            className="form-control"
                            placeholder="Introdu parola"
                            onChange={(e) =>
                                setState({ ...state, password: e.target.value })
                            }
                            required={true}
                        />
                    </div>
                    {errors()}
                    <div className="text-center">
                        <button
                            type="submit"
                            className="btn btn-success mt-3"
                            onClick={handleSubmit}
                        >
                            Conectează-te
                        </button>
                    </div>
                    </>
                )
            }
        </form>
    );
};

export default SignIn;
