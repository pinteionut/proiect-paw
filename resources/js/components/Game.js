import axios from "axios";
import { useEffect, useState } from "react";
import GameOver from "./GameOver";
import GameScreen from "./GameScreen";
import Player from "./Player";

const Game = () => {
    const [error, setError] = useState();
    const [game, setGame] = useState();
    const [myPlayerId, setMyPlayerId] = useState();

    useEffect(() => {
        const channel = window.location.pathname.replaceAll("/", "");
        Echo.leaveChannel(channel);
        Echo.channel(channel).listen("GameAction", (e) => {
            setGame({ ...e.message });
        });
        axios
            .post(`${window.location.pathname}/join`)
            .then(({ data }) => {
                setMyPlayerId(data.my_player_id);
            })
            .catch(() => {
                setError(true);
            });
        return () => {
            Echo.leaveChannel(channel);
        };
    }, []);

    if (error) {
        return <div className="text-center">Acest joc nu mai este disponibil.</div>
    }

    if (myPlayerId == undefined) {
        return <div className="text-center">Se încarcă...</div>;
    }

    console.log({ game });
    if (game && game.players) {
        console.log(Object.keys(game.players).find((key) => key != myPlayerId));
    }

    const gameScreen = () => {
        return (
            <>
                <Player
                    {...game.players[
                        Object.keys(game.players).find(
                            (key) => key != myPlayerId
                        )
                    ]}
                    nodeColor={"bg-warning"}
                    opponentNodeColor={"bg-danger"}
                />
                <GameScreen {...game} myPlayerId={myPlayerId} />
                <Player
                    {...game.players[myPlayerId]}
                    nodeColor={"bg-danger"}
                    opponentNodeColor={"bg-warning"}
                />
            </>
        );
    };

    const gameOver = () => {
        if (game.players[myPlayerId].state === "Win") {
            return <GameOver win={true} />;
        } else {
            return <GameOver />;
        }
    };

    const gameArea = () => {
        if (game?.state == "waiting_opponent") {
            return <div>Se așteaptă un adversar...</div>;
        }
        if (game?.state == "in_progress") {
            return gameScreen();
        }
        if (game?.state == "over") {
            return gameOver();
        }
    };

    return (
        <div className="text-center">
            <h1>Joc</h1>
            {gameArea()}
        </div>
    );
};

export default Game;
