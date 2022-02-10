import Node from "./Node";

const Player = (props) => {
    const borderClassName =
        props.state == "Opponent" ? "border-secondary" : "border-success shadow";

    const mapStateToAction = {
        Opponent: "E rândul celuilalt jucător ⏳",
        Place: "Plasează un pion pe tabla de joc ☝🏽",
        Attack: "Capturează un pion 💀",
        Move: "Alege pionul pe care dorești să îl muți 🔎",
        Moving: "Mută pionul ales 🏃"
    };
    console.log(props);
    return (
        <div className="mt-3">
            <div className={`card mb-3 ${borderClassName}`}>
                <div className={`card-header ${props.state == "Opponent" ? '' : 'bg-success text-white'}`}>{props.name}</div>
                <div className="card-body text-secondary">
                    <h5 className="card-title">
                        {mapStateToAction[props.state.split(' ')[0]]}
                    </h5>
                    <div className="card-text mt-3">
                        {props.nodesToPlace ? (
                            <>
                                <div className="mb-1">Pioni disponibili:</div>
                                {Array(props.nodesToPlace)
                                    .fill("")
                                    .map((_empty, i) => (
                                        <Node
                                            key={`${props.id}-node-${i}`}
                                            extraClasses={`${props.nodeColor} d-inline-block me-1`}
                                        />
                                    ))}
                            </>
                        ) : (
                            "Nu mai ai pioni disponibili."
                        )}
                    </div>
                    <div className="card-text mt-3">
                        {props.capturedNodes ? (
                            <>
                                <div className="mb-1">Pioni capturați:</div>
                                {Array(props.capturedNodes)
                                    .fill("")
                                    .map((_empty, i) => (
                                        <Node
                                            key={`${props.id}-node-${i}`}
                                            extraClasses={`${props.opponentNodeColor} d-inline-block me-1`}
                                        />
                                    ))}
                            </>
                        ) : (
                            "Nu ai capturat pioni până acum."
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Player;
