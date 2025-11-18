/**
 * RestauBoost Mobile App
 * @format
 */

import React, {useEffect} from 'react';
import {StatusBar, LogBox} from 'react-native';
import {NavigationContainer} from '@react-navigation/native';
import {GestureHandlerRootView} from 'react-native-gesture-handler';
import Toast from 'react-native-toast-message';
import {AuthProvider} from './src/context/AuthContext';
import AppNavigator from './src/navigation/AppNavigator';
import {colors} from './src/utils/theme';

// Ignore specific warnings
LogBox.ignoreLogs([
  'Non-serializable values were found in the navigation state',
]);

const App = () => {
  return (
    <GestureHandlerRootView style={{flex: 1}}>
      <AuthProvider>
        <NavigationContainer>
          <StatusBar
            barStyle="light-content"
            backgroundColor={colors.primary}
          />
          <AppNavigator />
          <Toast />
        </NavigationContainer>
      </AuthProvider>
    </GestureHandlerRootView>
  );
};

export default App;
