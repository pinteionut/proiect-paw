import { useEffect, useState } from "react";
import GameHistoryRow from './GameHistoryRow';

const GamesHistory = () => {
    const [games, setGames] = useState();

    useEffect(() => {
        axios.get("/games_history", {}).then(({ data }) => {
            setGames(data.games);
        });
    }, []);

    return (
        <div className="text-center">
            <h1>Istoric Jocuri</h1>
            {games ? (
                <>
                    {games.map((game) => (
                        <GameHistoryRow key={game.name} {...game} />
                    ))}
                </>
            ) : (
                "Se încarcă..."
            )}
        </div>
    );
};

export default GamesHistory;
