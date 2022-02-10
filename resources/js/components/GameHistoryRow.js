const GameHistoryRow = ({ name, status, date }) => {
    const statusHeader = {
        Win: <div className="card-header bg-success text-white">Câștigat</div>,
        Loss: <div className="card-header bg-danger text-white">Pierdut</div>,
    };

    return (
        <div className="card mt-3">
            {statusHeader[status]}
            <div className="card-body">
                <div className="card-title">{name}</div>
                <div className="card-text">{date}</div>
            </div>
        </div>
    );
};

export default GameHistoryRow;
