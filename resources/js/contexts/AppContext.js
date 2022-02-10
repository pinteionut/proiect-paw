import React, { createContext, useState } from 'react';

export const AppContext = createContext();

const AppProvider = (props) => {
  const [loggedIn, setLoggedIn] = useState(window.loggedIn);

  const state = {
    loggedIn, setLoggedIn
  };

  return (
    <AppContext.Provider value={{...state}}>
      { props.children }
    </AppContext.Provider>
  );
}

export default AppProvider;
