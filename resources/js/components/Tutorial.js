const Tutorial = () => {
    return (
        <div className="text-center">
            <h1>Regulile jocului</h1>
            <div className="card">
                <div className="card-body">
                    <h5 className="card-title">Cum se joacă moara?</h5>
                    <p className="card-text">
                        Găsiți la pagina atașată Regulile jocului de Moară
                        (Țintar).
                    </p>
                    <a
                        href="https://www.cumsejoaca.ro/reguli-si-regulamente/regulile-jocului-de-moara-tintar/"
                        className="btn btn-primary stretched-link"
                        target="_blank"
                    >
                        Accesează pagina
                    </a>
                </div>
            </div>
            <div className="card mt-3">
                <div className="card-body">
                    <h5 className="card-title">Video explicativ</h5>
                    <iframe
                        width="560"
                        height="315"
                        src="https://www.youtube.com/embed/jL5l_5jBTkc"
                        title="YouTube video player"
                        frameBorder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowFullScreen
                    ></iframe>
                </div>
            </div>
        </div>
    );
};

export default Tutorial;
