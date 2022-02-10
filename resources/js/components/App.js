import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import Header from "./Header";
import Welcome from "./Welcome";
import Menu from "./Menu";
import AppProvider from "../contexts/AppContext";
import SignUp from "./SignUp";
import SignIn from "./SignIn";
import Games from "./Games";
import Game from "./Game";
import Tutorial from "./Tutorial";
import GamesHistory from "./GamesHistory";

const App = () => {
    return (
        <AppProvider>
            <BrowserRouter>
                <Header />
                <div className="container mt-5">
                    <Routes>
                        <Route exact path="/" element={<Welcome />} />
                        <Route exact path="/menu" element={<Menu />} />
                        <Route exact path="/sign_in" element={<SignIn />} />
                        <Route exact path="/sign_up" element={<SignUp />} />
                        <Route exact path="/games" element={<Games />} />
                        <Route exact path="/game/:id" element={<Game />} />
                        <Route exact path="/tutorial" element={<Tutorial />} />
                        <Route exact path="/history" element={<GamesHistory />} />
                    </Routes>
                </div>
            </BrowserRouter>
        </AppProvider>
    );
};

export default App;

if (document.getElementById("app")) {
    ReactDOM.render(<App />, document.getElementById("app"));
}
