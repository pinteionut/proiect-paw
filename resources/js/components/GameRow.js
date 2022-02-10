import { useNavigate } from "react-router-dom";

const GameRow = ({ id }) => {
    const navigate = useNavigate();

    const joinGame = () => {
        navigate(`/game/${id}`);
    }
    return (
        <div className="card mt-3 game-card" onClick={joinGame}>
            <div className="card-body">Joc #{id}</div>
        </div>
    );
};

export default GameRow;
