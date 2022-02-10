import axios from "axios";
import { useContext, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { AppContext } from "../contexts/AppContext";
import GameRow from "./GameRow";

const Games = () => {
    const [loading, setLoading] = useState(true);
    const [games, setGames] = useState([]);
    const { loggedIn } = useContext(AppContext);
    const navigate = useNavigate();

    const fetchGames = () => {
        axios.get("/all_games", {}).then(({ data }) => {
            setLoading(false);
            setGames(data);
        });
    };

    useEffect(() => {
        if (!loggedIn) return;
        fetchGames();
    }, []);

    const createGame = () => {
        axios.post("/create_game", {}).then(({ data }) => {
            navigate(`/game/${data.id}`);
        });
    };

    const refresh = () => {
        setLoading(true);
        fetchGames();
    };

    if (!loggedIn) {
        return (
            <h1 className=" text-center text-danger">
                Conecteză-te pentru a accesa această pagină!
            </h1>
        );
    }
    return (
        <div className="text-center">
            <h1>Jocuri</h1>
            {loading ? (
                <p>Se încarcă...</p>
            ) : (
                <div className="mt-3">
                    <div className="btn btn-success" onClick={createGame}>
                        Creează joc
                    </div>
                    <div className="btn btn-secondary ms-3" onClick={refresh}>
                        Reîmprospătează
                    </div>
                    <hr></hr>
                    {games.length ? (
                        games.map((game) => (
                            <GameRow key={`game-${game.id}`} id={game.id} />
                        ))
                    ) : (
                        <div className="mt-3">Nu există jocuri disponibile.</div>
                    )}
                </div>
            )}
        </div>
    );
};

export default Games;
