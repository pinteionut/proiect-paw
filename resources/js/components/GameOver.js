const GameOver = (props) => {
  const bgClassName = props.win ? 'bg-success' : 'bg-danger';
  const content = props.win ? "Felicitări! Ai câștigat 🥇" : "Ai pierdut... ☹️"
  return (
    <div className="card">
      <div className={`card-header ${bgClassName}`}></div>
      <div className="card-body text-center">{content}</div>
      <div className={`card-footer ${bgClassName}`}></div>
    </div>
  )
}

export default GameOver;
