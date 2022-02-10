import axios from "axios";

const Node = (props) => {
    const handleClick = () => {
        if (!props.suggested) {
            return;
        }

        axios.post(`${window.location.pathname}/player_action`, {
            node: props.id,
            action: props.playerState,
        });
    };

    return (
        <div
            className={`node ${props.extraClasses} ${
                props.suggested ? "suggested" : ""
            }`}
            onClick={handleClick}
        ></div>
    );
};

export default Node;
