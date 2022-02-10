import Node from "./Node";

const GameScreen = (props) => {
    const playerState = props.players[props.myPlayerId].state;
    const node = (id, classNames) => {
        const isSuggested =
            playerState != "Opponent" && props.suggestedNodes.includes(id);
        const extraClasses = [];
        if (props.millNodes && props.millNodes.includes(id)) {
            extraClasses.push('bg-success');
        } else {
            if (props.tableNodes[id]) {
                if (props.tableNodes[id] == props.myPlayerId) {
                    extraClasses.push('bg-danger');
                } else {
                    extraClasses.push('bg-warning');
    
                }
            }
        }
        return (
            <div className={`node-container ${classNames}`}>
                <Node
                    id={id}
                    suggested={isSuggested}
                    playerState={playerState}
                    extraClasses={extraClasses.join(' ')}
                />
            </div>
        );
    };
    return (
        <div id="game-screen" className="mt-5 mb-5">
            <div id="big-box" className="m-auto">
                {node("big0", "left top")}
                {node("big1", "middle top")}
                {node("big2", "right top")}
                {node("big7", "left center")}
                {node("big3", "right center")}
                {node("big6", "left bottom")}
                {node("big5", "middle bottom")}
                {node("big4", "right bottom")}
                <div id="box-1"></div>
                <div id="box-2"></div>
                <div id="box-3"></div>
                <div id="box-4"></div>
                <div id="medium-box">
                    {node("mdm0", "left top")}
                    {node("mdm1", "middle top")}
                    {node("mdm2", "right top")}
                    {node("mdm7", "left center")}
                    {node("mdm3", "right center")}
                    {node("mdm6", "left bottom")}
                    {node("mdm5", "middle bottom")}
                    {node("mdm4", "right bottom")}
                    <div id="box-5"></div>
                    <div id="box-6"></div>
                    <div id="box-7"></div>
                    <div id="box-8"></div>
                    <div id="small-box">
                        {node("sml0", "left top")}
                        {node("sml1", "middle top")}
                        {node("sml2", "right top")}
                        {node("sml7", "left center")}
                        {node("sml3", "right center")}
                        {node("sml6", "left bottom")}
                        {node("sml5", "middle bottom")}
                        {node("sml4", "right bottom")}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default GameScreen;
